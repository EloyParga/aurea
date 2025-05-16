// CONTROL DEL SNAP SECTION

const sections = document.querySelectorAll('.snap-section')
let isScrolling = false;

window.addEventListener('wheel', (e) => {
    if (isScrolling) return;
    
    const direction = e-deltaY > 0 ? 1 : -1;
    const current = Math.round (window.scrollY / window.innerHeight);
    const target = Math.min(Math.max(current + direction, 0), sections.length)

    isScrolling = true;
    window.scrollTo({
        top: sections[target].offsetTop,
        behavior: 'smooth'
    });

    setTimeout(() => { isScrolling = false;}, 1000);
},
{
    passive: true
});

//------------------------------------------------------------------------------