// Pop Up newsletter
let sessionMessage = "";

window.onload = function() {
    if (sessionStorage.getItem('sessionMessage')) {
        sessionMessage = sessionStorage.getItem('sessionMessage');
        document.getElementById('customPopupMessage').innerText = sessionMessage;
        document.getElementById('customPopup').style.display = 'block';
        sessionStorage.removeItem('sessionMessage');
    }
};

function closeCustomPopup() {
    document.getElementById('customPopup').style.display = 'none';
}

// Function to show the popup
document.addEventListener('DOMContentLoaded', function() {
    const subscribeButton = document.querySelector('button[name="subscribe"]');
    
    if (subscribeButton) {
        subscribeButton.addEventListener('click', function(e) {
            e.preventDefault();
            sessionMessage = "Thanks for subscribe to our newsletter!";
            sessionStorage.setItem('sessionMessage', sessionMessage);
            document.getElementById('customPopupMessage').innerText = sessionMessage;
            document.getElementById('customPopup').style.display = 'block';
        });
    }
});



