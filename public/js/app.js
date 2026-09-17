
function updateClock() {

    const now = new Date();

    const options = {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
    };

    document.getElementById('clock').innerHTML =
        "Philippine Standard Time: " +
        now.toLocaleString('en-PH', options);

}

setInterval(updateClock, 1000);
updateClock();

let currentSlide = 0;

function openBook() {

    document.getElementById("bookModal")
        .style.display = "flex";

    showSlide(currentSlide);

}

function closeBook() {

    document.getElementById("bookModal")
        .style.display = "none";

}

function showSlide(index) {

    const slides =
        document.querySelectorAll(".book-slide");

    slides.forEach(slide => {
        slide.classList.remove("active");
    });

    slides[index]
        .classList.add("active");

}

function nextPage() {

    const slides =
        document.querySelectorAll(".book-slide");

    currentSlide++;

    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }

    showSlide(currentSlide);

}

function prevPage() {

    const slides =
        document.querySelectorAll(".book-slide");

    currentSlide--;

    if (currentSlide < 0) {
        currentSlide = slides.length - 1;
    }

    showSlide(currentSlide);

}
