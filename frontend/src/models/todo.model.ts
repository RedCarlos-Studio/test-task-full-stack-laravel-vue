export interface TodoI {
  readonly data: TodoDtoI
}

export interface TodoDtoI {
  readonly data: TodoDataI[],
  readonly links: TodoLinksI,
  readonly meta: TodoMetaI
}

export interface TodoDataI {
  readonly id: number,
  readonly title: string,
  readonly description: string,
  readonly is_completed: boolean,
  readonly is_deleted: boolean,
  readonly created_at: string
}

export interface TodoLinksI {
  readonly first: string,
  readonly last: string,
  readonly next: string | null,
  readonly prev: string | null
}

export interface TodoMetaI {
  readonly current_page: string,
  readonly from: number,
  readonly last_page: number,
  readonly links: string[],
  readonly path: string,
  readonly per_page: number,
  readonly to: number,
  readonly total: number
}
