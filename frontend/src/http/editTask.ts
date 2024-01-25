import axios from 'axios'

import { API_URL } from '@/config/config'

export const editTask = async (id: number, title: string, description: string) => await axios.put(`${ API_URL }/tasks/${ id }`, {
  title, description
})
