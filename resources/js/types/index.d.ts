export interface User {
  id: number
  network: Network
  name: string
  username: string
  telegram: string
  balance: number
  active: boolean
  contact_info: string
  percentage: number
  role: 'ahmed' | 'partner' | 'seller'
  telegram: string
  notes: string
  share: number
}

export interface Network {
  id: number
  name: string
  manager?: User
  partners?: Array<User>
  internet_price_per_week: number
  balance: number
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
  created_at_date: string
  updated_at: string
  updated_at_date: string
  items: Array<OrderItem>
  files: Array<OrderFile>
  can: {
    view: boolean
    update: boolean
    delete: boolean
  }
}

export interface OrderFile {
  id: number
  original_file_name: string
  extension: string
  size: number
  can: {
    download: boolean
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
  recipient: User
  user: User
  network: Network
  amount: number
  notes: string
  created_at: string
  created_at_date: string
  can: {
    view: boolean
    update: boolean
    delete: boolean
  }
}

export interface Expense {
  id: number
  user: User
  network: Network
  amount: number
  description: string
  created_at: string
  created_at_date: string
  can: {
    view: boolean
    update: boolean
    delete: boolean
  }
}

export interface Card {
  id: number
  name: string
  active: boolean
  price_for_consumer: number
  notes: string
}

export interface Transaction {
  id: number
  user: User
  manager: User
  network: Network
  amount: number
  description: string
  type: 'payment' | 'order' | 'expense'
  status: 'معلق' | 'مكتمل' | 'مرجع' | ''
  created_at: string
  created_at_date: string
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
    permissions: {
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
      transactions: {
        viewAny: boolean
      }
    }
  }
}

export type Filters = Record<Filter[]>
export type Filter = Record<string, any>
