
let forms = document.querySelectorAll('.collapse-form')
let collapseForms = []
let collapseButtons = document.querySelectorAll('#collapseController > input[type=radio]')

for (let form of forms) {
    collapseForms.push(new bootstrap.Collapse(form, { toggle: false }))
}

for (let i in collapseButtons) {
    collapseButtons[i].onclick = function () {
        let open = true
        if (forms[i].classList.contains('show')) {
            open = false
        }
        for (let form of forms) {
            form.classList.remove('show')
        }
        if (open) {
            forms[i].classList.add('show')
        }
    }
}
