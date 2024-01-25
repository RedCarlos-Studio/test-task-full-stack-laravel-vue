import axios from 'axios'

import { API_URL } from '@/config/config'

export const createTask = async (title: string, description: string) => await axios.post(`${ API_URL }/tasks`, { title, description })
