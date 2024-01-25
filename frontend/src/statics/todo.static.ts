import { TodoLinksI, TodoMetaI } from '@/models/todo.model'

export const INITIAL_TODO_LIST_PAGE = 1

export const INITIAL_TODO_LIST_LINKS: TodoLinksI = { first: '', last: '', next: null, prev: null }

export const INITIAL_TODO_LIST_META: TodoMetaI = {
  current_page: '',
  from: 0,
  last_page: 0,
  links: [],
  path: '',
  per_page: 0,
  to: 0,
  total: 0
}

export const INITIAL_NOTIFY = {
  isOpen: false,
  message: ''
}
