<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import OrderForm from './Partials/OrderForm.vue'

import { Card, User } from '@/types'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

const props = defineProps<{
  users: Array<User>
  cards: Array<Card>
  statuses: Array<string>
}>()

const initialValues = {
  status: 'معلق',
  cards: [
    {
      card_id: String(props.cards[0]?.id),
      number_of_packages: 1,
      number_of_cards_per_package: 120,
    },
  ],
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلبية جديدة
      </h2>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('orders.store')"
      @success="toast({ title: 'تم إضافة الطلب' })"
    >
      <template #default="{ values, setFieldValue }">
        <OrderForm
          :users="users"
          :cards="cards"
          :selected="values.user_id"
          @select="(selected: number) => setFieldValue('user_id', selected)"
        />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
