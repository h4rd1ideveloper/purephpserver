import FetchMyApi from './core'
export const api = ({ ...params }, cb) => FetchMyApi.call({ ...params }, cb);