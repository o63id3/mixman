export interface User {
  id: number
  region: Region
  name: string
  username: string
  balance: number
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
    payments: {
      viewAny: boolean
      create: boolean
      update: boolean
    }
    transactions: {
      viewAny: boolean
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
  status: 'طلب جديد' | 'مكتمل' | 'مرجع'
  action?: User
  total_price_for_seller: number
  total_price_for_consumer: number
  notes: string
  updated_at: string
  can: {
    view: boolean
    update: boolean
    delete: boolean
  }
}

export interface OrderItem {
  id: number
  order: Order
  card: Card
  number_of_packages: number
  number_of_cards_per_package: number
  quantity: number
  total_price_for_consumer: number
  total_price_for_seller: number
  can: {
    delete: boolean
  }
}

export interface Payment {
  id: number
  seller: User
  registerer: User
  amount: number
  notes: string
  created_at: string
}

export interface Transaction {
  id: number
  seller: User
  amount: number
  type: 'payment' | 'order'
  status: 'طلب جديد' | 'مكتمل' | 'مرجع' | ''
  created_at: string
}

interface Link {
  active: boolean
  url: string
  label: string
}

export interface Meta {
  current_page: number
  from: number
  path: string
  per_page: number
  to: number
  total: number
  last_page: number
}

export interface Links {
  first: string
  last: string
  prev: string
  next: string
}

export interface Paginator<T> {
  data: Array<T>
  meta: Meta
  links: Links
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User
    filters?: Array<Record<string, string>>
  }
}
