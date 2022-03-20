const sendMessage = document.querySelector('#send-message');
const chatDiv = document.querySelector('#chatDiv');

if (sendMessage) {

    sendMessage.addEventListener('click', () => {

        fetch('/?c=message', {
            method: 'POST',
            body: JSON.stringify({
                message: document.querySelector('#message').value
            })
        })
            .then(response => response.json())
            .then(response => {
                refreshChat(response);
            })
    });

    window.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click

            fetch('/?c=message', {
                method: 'POST',
                body: JSON.stringify({
                    message: document.querySelector('#message').value
                })
            })
                .then(response => response.json())
                .then(response => {
                    refreshChat(response);
                })

        }
    });
}

if (chatDiv) {

    window.addEventListener('load', () => {
        fetch('/?c=message&m=get-all', {method: 'POST'})
            .then(response => response.json())
            .then(response => {
                refreshChat(response);
            })
    })

    setInterval( function () {
        fetch('/?c=message&m=get-all', {method: 'POST'})
            .then(response => response.json())
            .then(response => {
                refreshChat(response);
            })
    }, 5000)
}

function refreshChat (messages) {
    chatDiv.innerHTML = ''
    for (let i = 0; i < messages.length; i++) {
        chatDiv.innerHTML += "<div><div>" + messages[i]['user'] + ' le ' + messages[i]['date'] + "</div><div>" + messages[i]['messageContent'] + "</div></div>"
    }
}