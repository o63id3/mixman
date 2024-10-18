<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { useLocalStorage, createReusableTemplate } from '@vueuse/core'
import { useI18n } from 'vue-i18n'

import {
  CircleUser,
  DollarSign,
  HandCoins,
  Home,
  LineChart,
  Menu,
  Network,
  Package,
  SheetIcon,
  ShoppingCart,
  Users,
} from 'lucide-vue-next'

import { Badge } from '@/Components/ui/badge'
import { Button } from '@/Components/ui/button'
import { Sheet, SheetContent, SheetTrigger } from '@/Components/ui/sheet'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import {
  ContextMenu,
  ContextMenuContent,
  ContextMenuItem,
  ContextMenuTrigger,
} from '@/Components/ui/context-menu'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { DialogDescription, DialogTitle } from '@/Components/ui/dialog'

const [NavLinkTemplate, NavLink] = createReusableTemplate()

const auth = usePage().props.auth

interface Link {
  route: string
  label: string
  visible: boolean
  badge?: number
  icon?: any
}

const { t } = useI18n()

const links: Array<Link> = [
  {
    route: 'dashboard',
    label: 'dashboard',
    visible: true,
    icon: Home,
  },
  {
    route: 'users.index',
    label: 'users',
    visible: auth.permissions.users.viewAny,
    icon: Users,
  },
  {
    route: 'cards.index',
    label: 'cards',
    visible: auth.permissions.cards.viewAny,
    icon: Package,
  },
  {
    route: 'networks.index',
    label: 'networks',
    visible: auth.permissions.networks.viewAny,
    icon: Network,
  },
  {
    route: 'orders.index',
    label: 'orders',
    visible: auth.permissions.orders.viewAny,
    badge: usePage().props.pendingOrders,
    icon: ShoppingCart,
  },
  {
    route: 'payments.index',
    label: 'payments',
    visible: auth.permissions.payments.viewAny,
    icon: DollarSign,
  },
  {
    route: 'expenses.index',
    label: 'expenses',
    visible: auth.permissions.expenses.viewAny,
    icon: HandCoins,
  },
  {
    route: 'transactions.index',
    label: 'transactions',
    visible: auth.permissions.transactions.viewAny,
    icon: LineChart,
  },
  {
    route: 'reports.index',
    label: 'reports',
    visible: auth.permissions.reports.viewAny,
    icon: SheetIcon,
  },
]

const sidebar = useLocalStorage('sidebar.visibility', false)
</script>

<template>
  <NavLinkTemplate v-slot="{ link }">
    <Link
      :href="route(link.route)"
      class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary"
      :class="[
        route().current(link.route)
          ? 'bg-muted text-primary'
          : 'text-muted-foreground',
      ]"
    >
      <component v-if="link.icon" :is="link.icon" class="h-4 w-4" />
      {{ t(`nav.${link.label}`) }}
      <Badge
        v-if="link.badge"
        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full ltr:ml-auto rtl:mr-auto"
      >
        {{ link.badge }}
      </Badge>
    </Link>
  </NavLinkTemplate>

  <div
    class="grid min-h-screen w-full bg-muted/40"
    :class="[
      sidebar ? 'md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]' : '',
    ]"
  >
    <div v-if="sidebar" class="hidden md:block ltr:border-r rtl:border-l">
      <div class="sticky top-0 flex h-full max-h-screen flex-col gap-2">
        <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
          <Link
            :href="route('dashboard')"
            class="flex items-center gap-2 font-semibold"
          >
            <ApplicationLogo
              class="block h-9 w-auto fill-current text-gray-800"
            />
            <span class="sr-only">مكس نت</span>
          </Link>
          <!-- <Button variant="outline" size="icon" class="rtl:mr-auto ltr:ml-auto h-8 w-8">
            <Bell class="h-4 w-4" />
            <span class="sr-only">Toggle notifications</span>
          </Button> -->
        </div>
        <div class="flex-1 overflow-y-auto">
          <nav class="grid items-start px-2 text-sm font-medium lg:px-4">
            <NavLink
              v-for="link in links.filter((link) => link.visible)"
              :key="link.label"
              :link="link"
            />
          </nav>
        </div>
      </div>
    </div>
    <div class="flex flex-col">
      <header
        class="flex h-14 items-center gap-4 border-b px-4 lg:h-[60px] lg:px-6"
      >
        <Sheet>
          <SheetTrigger as-child>
            <Button variant="outline" size="icon" class="shrink-0 md:hidden">
              <Menu class="h-5 w-5" />
              <span class="sr-only">Toggle navigation menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent
            side="right"
            class="flex flex-col"
            aria-describedby="asd"
          >
            <DialogDescription />
            <DialogTitle>
              <Link
                :href="route('dashboard')"
                class="flex items-center gap-2 text-lg font-semibold"
              >
                <ApplicationLogo class="h-7 w-7" />
                <span class="sr-only">مكس نت</span>
              </Link>
            </DialogTitle>

            <nav class="grid gap-2 overflow-y-auto text-lg font-medium">
              <NavLink
                v-for="link in links.filter((link) => link.visible)"
                :key="link.label"
                :link="link"
              />
            </nav>
          </SheetContent>
        </Sheet>
        <div>
          <ContextMenu>
            <ContextMenuTrigger>
              <!-- <div class="absolute inset-0 z-10 hidden md:block" /> -->

              <Button
                class="hidden md:flex"
                variant="outline"
                size="icon"
                @click="sidebar = !sidebar"
              >
                <Menu class="h-5 w-5" />
              </Button>
            </ContextMenuTrigger>
            <ContextMenuContent class="w-64">
              <ContextMenuItem
                v-for="link in links.filter((link) => link.visible)"
                :key="link.label"
                as-child
              >
                <NavLink :link="link" />
              </ContextMenuItem>
            </ContextMenuContent>
          </ContextMenu>
          <slot name="header" />
        </div>
        <div class="flex-1" />
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="secondary" size="icon" class="rounded-full">
              <CircleUser class="h-5 w-5" />
              <span class="sr-only">Toggle user menu</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-48">
            <DropdownMenuItem as-child>
              <Link :href="route('profile.edit')">حسابي</Link>
            </DropdownMenuItem>
            <DropdownMenuItem as-child>
              <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="w-full"
              >
                تسجيل الخروج
              </Link>
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </header>
      <header
        v-if="$slots.secondaryHeader"
        class="flex h-14 items-center gap-4 border-b px-4 lg:h-[60px] lg:px-6"
      >
        <slot name="secondaryHeader" />
      </header>

      <main class="grid grid-cols-1 py-4 lg:px-2">
        <slot />
      </main>
    </div>
  </div>
</template>
