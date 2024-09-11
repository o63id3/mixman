export interface User {
  id: number
  region: Region
  name: string
  username: string
  balance: Number
  admin: boolean
  active: boolean
  contact_info: string
  notes: string
  can: {
    sellers: {
      viewAny: boolean
      create: boolean
      update: boolean
    }
    regions: {
      viewAny: boolean
      create: boolean
      update: boolean
    }
    cards: {
      viewAny: boolean
      create: boolean
      update: boolean
    }
  }
}

export interface Region {
  id: number
  name: string
}

export interface Card {
  id: number
  name: string
  active: boolean
  price: number
  notes: string
}

interface Link {
  active: boolean
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
