const section1 = document.querySelector(".section1");
const nav = document.querySelector(".nav");

const obsCallback = function (entries, observer) {
    const [entry] = entries;

    if (!entry.isIntersecting) nav.classList.add("sticky");
    else nav.classList.remove("sticky");
};

const obsObject = {
    root: null,
    threshold: 0.1,
    rootMargin: `-10px`,
};

const headObserver = new IntersectionObserver(obsCallback, obsObject);
headObserver.observe(section1);

const section2 = document.querySelector(".section2");

(function ($) {
    // Animate bar menu
    $(".hamburger-menu").on("click", function () {
        $(".bar").toggleClass("animate");
        if ($("body").hasClass("open-menu")) {
            $("body").removeClass("open-menu");
        } else {
            $("body").toggleClass("open-menu");
        }
    });

    // Close menu when press esc
    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            $(".bar").removeClass("animate");
            $("body").removeClass("open-menu");
        }
    });
})(jQuery);