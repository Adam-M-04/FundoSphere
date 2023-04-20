let favoriteButton = document.getElementById('favorite-button')

function sendRequest(id) {
    fetch('/fundraiser/favorite/handler/' + id, {
        method: 'POST'
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.log("An error occurred")
                alert("An error occurred. Try again later.")
                return
            }
            let newVal = data.new_value
            favoriteButton.classList.remove('bi-heart-fill')
            favoriteButton.classList.remove('bi-heart')
            favoriteButton.classList.add(newVal ? 'bi-heart-fill' : 'bi-heart')
        });
}

favoriteButton.onclick = () => {sendRequest(id)}
