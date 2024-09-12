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
    orders: {
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
  price_for_consumer: number
  price_for_seller: number
  notes: string
}

export interface Order {
  id: number
  seller: User
  status: 'C' | 'P' | 'X'
  action?: User
  notes: string
  can: {
    view: boolean
    update: boolean
    delete: boolean
  }
}

interface Link {
  active: boolean
  url: string
  label: string
}

export interface Paginator<T> {
  data: Array<T>
  meta: {
    current_page: number
    from: number
    path: string
    per_page: number
    to: number
    total: number
  }
  links: {
    first_page_url: string
    last_page: number
    last_page_url: string
    next_page_url: string
    prev_page_url: null
  }
  //   links: Array<Link>
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User
  }
}
