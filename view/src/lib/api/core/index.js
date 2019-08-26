import axios from 'axios'
class FetchMyApi {
    constructor () {
        this.api = axios.create()
    }
    async call ({ ...params }, callback) {
        try {
            const res = await this.api({ ...params });
            callback(res.data)
        } catch (e) {
            callback(new Error(`Fail to Fetch  ${e}`))
        }
    }
}
export default new FetchMyApi()