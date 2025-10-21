// Smooth page transitions for Pathfinder Career Guidance Platform
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to page content
    const body = document.body;
    body.style.opacity = '0';
    body.style.transition = 'opacity 0.3s ease-in-out';

    // Fade in the page
    setTimeout(() => {
        body.style.opacity = '1';
    }, 50);

    // Handle smooth transitions for navigation links
    const navLinks = document.querySelectorAll('a[href*="login"], a[href*="register"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');

            // Fade out current page
            body.style.opacity = '0';

            // Navigate to new page after fade out
            setTimeout(() => {
                window.location.href = href;
            }, 200);
        });
    });

    // Add smooth hover effects to form elements
    const formInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.2s ease-in-out';
        });

        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('button, .btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    button, .btn {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
