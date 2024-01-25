import axios from 'axios'

import { API_URL } from '@/config/config'

export const openTask = async (id: number) => await axios.get(`${ API_URL }/tasks/${ id }/set-as-open`)
