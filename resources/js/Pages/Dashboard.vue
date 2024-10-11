<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'

import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import {
  CircleCheck,
  CircleDashed,
  CircleFadingArrowUp,
  DollarSign,
} from 'lucide-vue-next'
import { h } from 'vue'
import CardDescription from '@/Components/ui/card/CardDescription.vue'
import CardFooter from '@/Components/ui/card/CardFooter.vue'
import { toast } from '@/Components/ui/toast'
import Button from '@/Components/ui/button/Button.vue'
import { formatMoney } from '@/lib/formatters'

const props = defineProps<{
  total_debuts: number
  number_of_pending_orders: number
  number_of_returned_orders_last_week: number
  number_of_completed_orders_last_week: number
  max_network_income?: {
    network: string
    amount: number
  }
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
        title: 'الطلبات المعلقة',
        value:
          user.role !== 'seller'
            ? h(
                Link,
                {
                  href: `/orders?filter[status]=معلق&filter[manager]=${user.id}`,
                  class: 'hover:underline',
                },
                {
                  default: () => `${props.number_of_pending_orders} طلب`,
                },
              )
            : h('span', `${props.number_of_pending_orders} طلب`),
        icon: h(CircleDashed, { class: 'text-yellow-500' }),
        visible: true,
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
        visible:
          user.role !== 'seller' &&
          props.total_income !== undefined &&
          props.total_income > 0,
      },
      {
        title: 'أكبر مدخول شبكة',
        value: h('span', props.max_network_income?.network ?? ''),
        description: `${formatMoney(props.max_network_income?.amount)} شيكل`,
        icon: h(DollarSign, { class: 'text-green-500' }),
        visible: user.role === 'ahmed',
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
        visible: user.role !== 'seller',
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
      <div v-if="user.role !== 'ahmed'">
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
                  form.post(route('user-orders.store'), {
                    preserveScroll: true,
                    preserveState: false,
                    onSuccess: () => toast({ title: 'تم إرسال الطلب بنجاح' }),
                  })
                "
                :disabled="form.processing"
                :loading="form.processing"
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
