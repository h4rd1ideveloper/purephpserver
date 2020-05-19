const json_headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};
window.axios = ({url, method = 'POST', data = null, headers = json_headers}) => async (cb) => {
    try {
        cb(await (await fetch(url, {method, headers, body: data ? JSON.stringify(data) : null})).json());
    } catch (e) {
        console.trace(e, url, method, data, headers)
        cb({error: true})
    }
}