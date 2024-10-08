<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'

import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import {
  CircleCheck,
  CircleDashed,
  CircleFadingArrowUp,
  DollarSign,
  UserRound,
} from 'lucide-vue-next'
import { User } from '@/types'
import { h } from 'vue'
import { formatMoney } from '@/lib/money'
import CardDescription from '@/Components/ui/card/CardDescription.vue'
import CardFooter from '@/Components/ui/card/CardFooter.vue'
import { toast } from '@/Components/ui/toast'
import Button from '@/Components/ui/button/Button.vue'

const props = defineProps<{
  total_debuts: number
  number_of_pending_orders: number
  number_of_returned_orders_last_week: number
  number_of_completed_orders_last_week: number
  max_debut_seller?: {
    seller: User
    amount: number
  }
  max_region_income?: {
    region: string
    amount: number
  }
  max_seller_income?: {
    seller: string
    amount: number
  }
  sellers_count?: number
  total_income?: number
}>()

const user = usePage().props.auth.user

interface Card {
  title: string
  value: any
  description?: string
  icon: any
  visible: boolean
}

interface Group {
  title: string
  cards: Array<Card>
}

const groups: Array<Group> = [
  {
    title: 'إحصائيات عامة',
    cards: [
      {
        title: 'الديون المستحقة',
        value: h('span', `${formatMoney(props.total_debuts)} شيكل`),
        icon: h(DollarSign, { class: 'text-green-500' }),
        visible: props.total_debuts !== 0,
      },
      {
        title: 'أكبر دين',
        value: h('span', props.max_debut_seller?.seller?.name ?? ''),
        description: `الدين المستحق ${formatMoney(props.max_debut_seller?.amount)} شيكل`,
        icon: h(DollarSign, { class: 'text-green-500' }),
        visible: user.admin,
      },
      {
        title: 'الطلبات المعلقة',
        value: user.admin
          ? h(
              Link,
              { href: `/orders?filter[status]=معلق`, class: 'hover:underline' },
              {
                default: () => `${props.number_of_pending_orders} طلب`,
              },
            )
          : h('span', `${props.number_of_pending_orders} طلب`),
        icon: h(CircleDashed, { class: 'text-yellow-500' }),
        visible: true,
      },
      {
        title: 'الباعة',
        value: h('span', `${props.sellers_count} بائع`),
        icon: h(UserRound),
        visible: user.admin,
      },
    ],
  },
  {
    title: 'إحصائيات الأسبوع الماضي',
    cards: [
      {
        title: 'صافي المدخول',
        value: h('span', `${formatMoney(props.total_income)} شيكل`),
        icon: h(DollarSign, { class: 'text-green-500' }),
        visible: user.admin,
      },
      {
        title: 'أكبر مدخول منطقة',
        value: h('span', props.max_region_income?.region ?? ''),
        description: `${formatMoney(props.max_region_income?.amount)} شيكل`,
        icon: h(DollarSign, { class: 'text-green-500' }),
        visible: user.admin,
      },
      {
        title: 'أكبر مدخول بائع',
        value: h('div', [
          h('span', props.max_seller_income?.seller),
          // h(
          //   'span',
          //   {
          //     class:
          //       'text-xs tracking-wide font-normal mr-1 text-muted-foreground',
          //   },
          //   `(${props.max_seller_income?.seller})`,
          // ),
        ]),
        description: `${formatMoney(props.max_seller_income?.amount)} شيكل`,
        icon: h(DollarSign, { class: 'text-green-500' }),
        visible: user.admin,
      },

      {
        title: 'الطلبات المكتملة',
        value: h('span', `${props.number_of_completed_orders_last_week} طلب`),
        icon: h(CircleCheck, { class: 'text-green-500' }),
        visible: true,
      },
      {
        title: 'الطلبات المرجعة',
        value: h('span', `${props.number_of_returned_orders_last_week} طلب`),
        icon: h(CircleFadingArrowUp, { class: 'text-red-500' }),
        visible: true,
      },
    ],
  },
]

const form = useForm({})
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <div v-if="!user.admin">
        <h2 class="px-4 font-semibold"># الطلبات</h2>
        <div class="mt-2 grid gap-2 md:grid-cols-2 md:gap-4 lg:grid-cols-4">
          <Card class="rounded-none sm:col-span-2 sm:rounded-xl">
            <CardHeader class="pb-3">
              <CardTitle>طلباتي</CardTitle>
              <CardDescription class="max-w-lg text-balance leading-relaxed">
                بإمكانك طلب حزمة كروت جديدة مرة واحدة يومياً
              </CardDescription>
            </CardHeader>
            <CardFooter>
              <Button
                @click="
                  form.post(route('seller-orders.store'), {
                    preserveScroll: true,
                    onSuccess: () => toast({ title: 'تم إرسال الطلب بنجاح' }),
                  })
                "
                :disabled="form.processing"
              >
                طلب كروت
              </Button>
            </CardFooter>
          </Card>
        </div>
      </div>
      <div v-for="group in groups" :key="group.title">
        <h2 class="px-4 font-semibold"># {{ group.title }}</h2>
        <div class="mt-2 grid gap-2 md:grid-cols-2 md:gap-4 lg:grid-cols-4">
          <Card
            class="rounded-none sm:rounded-xl"
            v-for="card in group.cards.filter((card) => card.visible)"
            :key="card.title"
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
              <div class="text-2xl font-bold">
                <component :is="card.value" />
              </div>
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
