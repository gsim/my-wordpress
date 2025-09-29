<?php

final class WP_Hook implements Iterator, ArrayAccess {

    public array $callbacks = [];
    private array $iterations = [];
    private array $current_priority = [];
    private int $nesting_level = 0;
    private bool $doing_action = false;

    public function add_filter(string $hook_name, callable|string|array $callback, int $priority, int $accepted_args): void {
        $idx = _wp_filter_build_unique_id($hook_name, $callback, $priority);
        $priority_existed = isset($this->callbacks[$priority]);
        $this->callbacks[$priority][$idx] = [
            'function' => $callback,
            'accepted_args' => $accepted_args,
        ];
        if (!$priority_existed && count($this->callbacks) > 1) {
            ksort($this->callbacks, SORT_NUMERIC);
        }
        if ($this->nesting_level > 0) {
            $this->resort_active_iterations($priority, $priority_existed);
        }
    }

    private function resort_active_iterations(int|bool $new_priority = false, bool $priority_existed = false): void {
        $new_priorities = array_keys($this->callbacks);
        if (!$new_priorities) {
            foreach ($this->iterations as $index => $iteration) {
                $this->iterations[$index] = $new_priorities;
            }
            return;
        }
        $min = min($new_priorities);
        foreach ($this->iterations as $index => &$iteration) {
            $current = current($iteration);
            if ($current === false) {
                continue;
            }
            $iteration = $new_priorities;
            if ($current < $min) {
                array_unshift($iteration, $current);
                continue;
            }
            while (current($iteration) < $current) {
                if (next($iteration) === false) {
                    break;
                }
            }
            if ($new_priority === $this->current_priority[$index] && !$priority_existed) {
                $prev = (false === current($iteration)) ? end($iteration) : prev($iteration);
                if ($prev === false) {
                    reset($iteration);
                } elseif ($new_priority !== $prev) {
                    next($iteration);
                }
            }
        }
        unset($iteration);
    }

    public function remove_filter(string $hook_name, callable|string|array $callback, int $priority): bool {
        $function_key = _wp_filter_build_unique_id($hook_name, $callback, $priority);
        $exists = isset($this->callbacks[$priority][$function_key]);
        if ($exists) {
            unset($this->callbacks[$priority][$function_key]);
            if (!$this->callbacks[$priority]) {
                unset($this->callbacks[$priority]);
                if ($this->nesting_level > 0) {
                    $this->resort_active_iterations();
                }
            }
        }
        return $exists;
    }

    public function has_filter(string $hook_name = '', callable|string|array|bool $callback = false): int|bool {
        if ($callback === false) {
            return $this->has_filters();
        }
        $function_key = _wp_filter_build_unique_id($hook_name, $callback, false);
        if (!$function_key) {
            return false;
        }
        foreach ($this->callbacks as $priority => $callbacks) {
            if (isset($callbacks[$function_key])) {
                return $priority;
            }
        }
        return false;
    }

    public function has_filters(): bool {
        foreach ($this->callbacks as $callbacks) {
            if ($callbacks) {
                return true;
            }
        }
        return false;
    }

    public function remove_all_filters(int|bool $priority = false): void {
        if (!$this->callbacks) {
            return;
        }
        if ($priority === false) {
            $this->callbacks = [];
        } elseif (isset($this->callbacks[$priority])) {
            unset($this->callbacks[$priority]);
        }
        if ($this->nesting_level > 0) {
            $this->resort_active_iterations();
        }
    }

    public function apply_filters(mixed $value, array $args): mixed {
        if (!$this->callbacks) {
            return $value;
        }
        $nesting_level = $this->nesting_level++;
        $this->iterations[$nesting_level] = array_keys($this->callbacks);
        $num_args = count($args);
        do {
            $this->current_priority[$nesting_level] = current($this->iterations[$nesting_level]);
            $priority = $this->current_priority[$nesting_level];
            foreach ($this->callbacks[$priority] as $the_) {
                if (!$this->doing_action) {
                    $args[0] = $value;
                }
                if ($the_['accepted_args'] == 0) {
                    $value = call_user_func($the_['function']);
                } elseif ($the_['accepted_args'] >= $num_args) {
                    $value = call_user_func_array($the_['function'], $args);
                } else {
                    $value = call_user_func_array($the_['function'], array_slice($args, 0, (int)$the_['accepted_args']));
                }
            }
        } while (next($this->iterations[$nesting_level]) !== false);
        unset($this->iterations[$nesting_level]);
        unset($this->current_priority[$nesting_level]);
        $this->nesting_level--;
        return $value;
    }

    public function do_action(array $args): void {
        $this->doing_action = true;
        $this->apply_filters('', $args);
        if (!$this->nesting_level) {
            $this->doing_action = false;
        }
    }

    public function do_all_hook(array $args): void {
        $nesting_level = $this->nesting_level++;
        $this->iterations[$nesting_level] = array_keys($this->callbacks);
        do {
            $priority = current($this->iterations[$nesting_level]);
            foreach ($this->callbacks[$priority] as $the_) {
                call_user_func_array($the_['function'], $args);
            }
        } while (next($this->iterations[$nesting_level]) !== false);
        unset($this->iterations[$nesting_level]);
        $this->nesting_level--;
    }

    public function current_priority(): int|bool {
        if (current($this->iterations) === false) {
            return false;
        }
        return current(current($this->iterations));
    }

    public static function build_preinitialized_hooks(array $filters): array {
        $normalized = [];
        foreach ($filters as $hook_name => $callback_groups) {
            if (is_object($callback_groups) && $callback_groups instanceof WP_Hook) {
                $normalized[$hook_name] = $callback_groups;
                continue;
            }
            $hook = new WP_Hook();
            foreach ($callback_groups as $priority => $callbacks) {
                foreach ($callbacks as $cb) {
                    $hook->add_filter($hook_name, $cb['function'], $priority, $cb['accepted_args']);
                }
            }
            $normalized[$hook_name] = $hook;
        }
        return $normalized;
    }

    public function offsetExists(mixed $offset): bool {
        return isset($this->callbacks[$offset]);
    }

    public function offsetGet(mixed $offset): ?array {
        return isset($this->callbacks[$offset]) ? $this->callbacks[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        if (is_null($offset)) {
            $this->callbacks[] = $value;
        } else {
            $this->callbacks[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void {
        unset($this->callbacks[$offset]);
    }

    public function current(): array {
        return current($this->callbacks);
    }

    public function next(): void {
        next($this->callbacks);
    }

    public function key(): mixed {
        return key($this->callbacks);
    }

    public function valid(): bool {
        return key($this->callbacks) !== null;
    }

    public function rewind(): void {
        reset($this->callbacks);
    }

}