var floatHeader = false;
var lastFloatHeader = false;
var isIndex = false;
window.onscroll = function() {
    if (isIndex) {
        document.getElementById("header-image").style.backgroundPosition = "0px " + (window.scrollY / 1.5) + "px";
    }
    floatHeader = window.scrollY > 150;
    if (floatHeader != lastFloatHeader) {
        lastFloatHeader = floatHeader;
        var header = document.getElementById("header");
        if (floatHeader) {
            header.classList.add("float-header");
        } else {
            header.classList.remove("float-header");
        }
    }
};
window.addEventListener("load", function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
