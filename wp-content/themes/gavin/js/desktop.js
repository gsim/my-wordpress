document.querySelectorAll('.menu-parent').forEach(function(parent){
    const submenu = parent.querySelector('.child-menu');
    if(submenu){
        parent.addEventListener('mouseenter', function(){
            const rect = parent.getBoundingClientRect();
            const menuRect = document.querySelector('.horizontal-menu').getBoundingClientRect();
            // Set the top relative to .horizontal-menu
            submenu.style.top = (rect.bottom - menuRect.top) + 'px';
        });
    }
});
