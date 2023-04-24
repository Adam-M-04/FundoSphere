
// message toast
const toastBootstrap = window.bootstrap.Toast.getOrCreateInstance(document.getElementById('liveToast'))

document.getElementById('share-button').addEventListener('click', event => {
    if (navigator.share) {
        navigator.share({
            title: 'FundoSphere - {{ fundraiser.title }}',
            url: url
        }).then(() => {
            console.log('Thanks for sharing!');
        })
            .catch(console.error);
    } else {
        navigator.clipboard.writeText(url).then(function () {
            toastBootstrap.show()
        })
    }
});

// error toast
const toastBootstrapError = window.bootstrap.Toast.getOrCreateInstance(document.getElementById('errorToast'))
if (error) {
    toastBootstrapError.show()
}

