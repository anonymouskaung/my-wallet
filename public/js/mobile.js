    function openPage(event, pageName) {
        const tabContents = document.querySelectorAll('.tab-content');
        const navLinks = document.querySelectorAll('.nav-link');
        tabContents.forEach(item => {
            item.style.display = 'none';
        });
        navLinks.forEach(item => {
            item.classList.remove('active');
        });
        if(event) {
            event.currentTarget.classList.add('active');
        }
        const page = document.getElementById(pageName);
        if(page) {
            page.style.display = 'block';
        }
    }