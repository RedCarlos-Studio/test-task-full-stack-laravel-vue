import { ApiResponse } from '@/models/http.model'

export const setError = (err: ApiResponse) =>
  err.response.data.data
    ? `${ Object.values(err.response.data.data).map(item => item[0]).join(' ') }`
    : err.message
