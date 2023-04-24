let favoriteButton = document.getElementById('favorite-button')
const toastBootstrapError = window.bootstrap.Toast.getOrCreateInstance(document.getElementById('errorToast'))

function sendRequest(id) {
    fetch('/fundraiser/favorite/handler/' + id, {
        method: 'POST'
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.log("An error occurred")
                toastBootstrapError.show()
                return
            }
            let newVal = data.new_value
            favoriteButton.classList.remove('bi-heart-fill')
            favoriteButton.classList.remove('bi-heart')
            favoriteButton.classList.add(newVal ? 'bi-heart-fill' : 'bi-heart')
        });
}

favoriteButton.onclick = () => {sendRequest(id)}
