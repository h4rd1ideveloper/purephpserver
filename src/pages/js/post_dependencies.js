window.el = (dcm = document) => ({
    id: (target) => dcm.getElementById(target),
    selector: (target) => dcm.querySelector(target),
    selectorAll: (target) => dcm.querySelectorAll(target),
})
window.fetch_data = async (t, b) => {
    b.disabled = true;
    console.log(t.value)
    b.disabled = false;
}
window.create_post = (tx, bn) => (e) => {
    e.preventDefault();
    fetch_data(tx, bn)
        .then(console.log)
        .catch(console.error)
    return false;
}