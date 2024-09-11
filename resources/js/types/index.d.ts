export interface User {
  id: number
  region: Region
  name: string
  username: string
  balance: Number
  admin: Boolean
  active: Boolean
  contact_info: string
  notes: string
  can: {
    sellers: {
      viewAny: Boolean
      create: Boolean
      update: Boolean
    }
    regions: {
      viewAny: Boolean
      create: Boolean
      update: Boolean
    }
  }
}

export interface Region {
  id: number
  name: string
}

interface Link {
  active: Boolean
  url: string
  label: string
}

export interface Paginator<T> {
  current_page: number
  data: Array<T>
  first_page_url: string
  from: number
  last_page: number
  last_page_url: string
  links: Array<Link>
  next_page_url: string
  path: string
  per_page: number
  prev_page_url: null
  to: number
  total: number
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User
  }
}
