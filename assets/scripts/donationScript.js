
let customInput = document.querySelector('#donationInput')

customInput.onchange = () => {
    customInput.value = Math.ceil(Math.max(Math.min(customInput.value, 10000), 1))
}
