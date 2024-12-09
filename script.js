// JavaScript to toggle the menu on smaller screens
const menuBtn = document.getElementById('menu-btn');
const nav = document.querySelector('nav');

menuBtn.addEventListener('click', () => {
    nav.classList.toggle('active');
});

const navLinks = document.querySelectorAll('nav ul li a');

navLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default anchor click behavior

        const targetId = this.getAttribute('href'); // Get the target section ID
        const targetSection = document.querySelector(targetId); // Select the target section

        // Scroll to the target section
        targetSection.scrollIntoView({
            behavior: 'smooth', 
            block: 'start' 
        });
    });
});

// JavaScript for form validation and user interaction
const bookingForm = document.querySelector('.contact-form');
const doctorSelect = document.getElementById('doctor');
const dateInput = document.getElementById('date');
const timeInput = document.getElementById('time');
const submitBtn = document.querySelector('.contact-form .link-btn');

bookingForm.addEventListener('submit', (event) => {
    event.preventDefault();

    // Validate form inputs
    if (!doctorSelect.value) {
        alert('Please select a doctor.');
        return;
    }

    if (!dateInput.value) {
        alert('Please select a date.');
        return;
    }

    if (!timeInput.value) {
        alert('Please select a time.');
        return;
    }

    // Submit the form
    bookingForm.submit();
});
