export interface User {
  id: number
  region: Region
  name: string
  username: string
  balance: number
  admin: boolean
  active: boolean
  contact_info: string
  percentage: number
  telegram: string
  notes: string
  can: {
    users: {
      viewAny: boolean
    }
    cards: {
      viewAny: boolean
    }
    networks: {
      viewAny: boolean
    }
    orders: {
      viewAny: boolean
    }
    payments: {
      viewAny: boolean
    }
    expenses: {
      viewAny: boolean
    }
    // regions: {
    //   viewAny: boolean
    // }
    // cards: {
    //   viewAny: boolean
    // }
    // payments: {
    //   viewAny: boolean
    // }
    // transactions: {
    //   viewAny: boolean
    // }
  }
}

export interface Network {
  id: number
  name: string
  manager?: User
  internet_price_per_week: number
  active: boolean
}

export interface Order {
  id: number
  status: 'معلق' | 'مكتمل' | 'مرجع'
  orderer: User
  manager: User
  network: Network
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

export interface Payment {
  id: number
  recipient: User
  user: User
  network: Network
  amount: number
  notes: string
  created_at: string
}

export interface Expense {
  id: number
  user: User
  network: Network
  amount: number
  description: string
  created_at: string
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
  //   price_for_seller: number
  notes: string
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
  pendingOrders: number
  auth: {
    user: User
  }
}

export type Filters = Record<Filter[]>
export type Filter = Record<string, any>
