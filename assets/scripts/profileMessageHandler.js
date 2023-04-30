
const toastBootstrap = window.bootstrap.Toast.getOrCreateInstance(document.getElementById('messageToast'))
if (message) {
    toastBootstrap.show()
}

