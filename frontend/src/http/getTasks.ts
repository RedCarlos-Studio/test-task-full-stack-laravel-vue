import axios from 'axios'

import { API_URL } from '@/config/config'

export const getTasks = async (newPage?: number) => await axios.get(`${ API_URL }/tasks${ newPage ? `?page=${ newPage }` : '' }`)
