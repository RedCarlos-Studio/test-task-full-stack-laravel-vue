export const TITLE_ERROR = 'Title is required field!'

export const DESCRIPTION_ERROR = 'Description is required field!'

export const minLengthError = (name: string) => `The ${ name } field must be at least 3 characters.`

export const maxLengthError = (name: string) => {
  switch (name) {
  case 'title':
    return 'Max length 255 symbols!'
  case 'description':
    return 'Max length 750 symbols!'
  default:
    return ''
  }
}
