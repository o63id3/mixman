<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import {
  CircleCheck,
  CircleDashed,
  CircleFadingArrowUp,
  DollarSign,
  UserRound,
} from 'lucide-vue-next'
import { Region, User } from '@/types'
import { h } from 'vue'
import { formatMoney } from '@/lib/money'
import CardDescription from '@/Components/ui/card/CardDescription.vue'
import CardFooter from '@/Components/ui/card/CardFooter.vue'
import { cn } from '@/lib/utils'
import { buttonVariants } from '@/Components/ui/button'
import { toast } from '@/Components/ui/toast'

const props = defineProps<{
  total_debuts: number
  number_of_pending_orders: number
  number_of_returned_orders_last_week: number
  number_of_completed_orders_last_week: number
  max_debut_seller?: {
    seller?: User
    amount?: number
  }
  max_region_income?: {
    region?: Region | null
    amount?: number
  }
  sellers_count?: number
}>()

const user = usePage().props.auth.user

interface Card {
  title: string
  value: string | number
  description?: string
  icon: Object
  visible: boolean
}

const cards: Array<Card> = [
  {
    title: 'الديون المستحقة',
    value: `${formatMoney(props.total_debuts)} شيكل`,
    icon: h(DollarSign, { class: 'text-green-500' }),
    visible: props.total_debuts > 0,
  },
  {
    title: 'أكبر دين',
    value: props.max_debut_seller?.seller?.name ?? '',
    description: `الدين المستحق ${formatMoney(props.max_debut_seller?.amount)} شيكل`,
    icon: h(DollarSign, { class: 'text-green-500' }),
    visible: user.admin && props.max_debut_seller?.seller !== null,
  },
  {
    title: 'أكبر مدخول الأسبوع الماضي',
    value: props.max_region_income?.region?.name ?? '',
    description: `${formatMoney(props.max_region_income?.amount)} شيكل`,
    icon: h(DollarSign, { class: 'text-green-500' }),
    visible: user.admin && props.max_region_income?.region !== null,
  },
  {
    title: 'الباعة',
    value: `${props.sellers_count} بائع`,
    icon: UserRound,
    visible: user.admin,
  },
  {
    title: 'الطلبات المعلقة',
    value: `${props.number_of_pending_orders} طلب`,
    icon: h(CircleDashed, { class: 'text-yellow-500' }),
    visible: true,
  },
  {
    title: 'الطلبات المكتملة الأسبوع الماضي',
    value: `${props.number_of_completed_orders_last_week} طلب`,
    icon: h(CircleCheck, { class: 'text-green-500' }),
    visible: true,
  },
  {
    title: 'الطلبات المرجعة الأسبوع الماضي',
    value: `${props.number_of_returned_orders_last_week} طلب`,
    icon: h(CircleFadingArrowUp, { class: 'text-red-500' }),
    visible: true,
  },
]
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="py-8">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
          <Card
            v-if="!user.admin"
            class="rounded-none sm:col-span-2 sm:rounded-xl"
          >
            <CardHeader class="pb-3">
              <CardTitle>طلباتي</CardTitle>
              <CardDescription class="max-w-lg text-balance leading-relaxed">
                بإمكانك طلب حزمة كروت جديدة مرة واحدة يومياً
              </CardDescription>
            </CardHeader>
            <CardFooter>
              <Link
                :href="route('seller-orders.store')"
                method="post"
                as="button"
                :class="cn(buttonVariants({ variant: 'default' }))"
                @success="() => toast({ title: 'تم إرسال الطلب بنجاح' })"
              >
                طلب كروت
              </Link>
            </CardFooter>
          </Card>
          <Card
            class="rounded-none sm:rounded-xl"
            v-for="card in cards.filter((card) => card.visible)"
          >
            <CardHeader
              class="flex flex-row items-center justify-between space-y-0 pb-2"
            >
              <CardTitle class="text-sm font-medium">
                {{ card.title }}
              </CardTitle>
              <component :is="card.icon" class="h-4 w-4" />
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold">{{ card.value }}</div>
              <p v-if="card.description" class="text-xs text-muted-foreground">
                {{ card.description }}
              </p>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
