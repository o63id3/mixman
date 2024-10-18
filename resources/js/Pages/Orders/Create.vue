<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { formSchema } from './definitions'
import OrderForm from './Partials/OrderForm.vue'

import { CardTitle } from '@/Components/ui/card'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { Card, User } from '@/types'

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

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الطلبات',
    route: route('orders.index'),
  },
  {
    label: 'إنشاء',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('orders.store')"
    >
      <template #title>
        <CardTitle> طلبية جديدة </CardTitle>
      </template>

      <template #default="{ form }">
        <OrderForm
          :users="users"
          :cards="cards"
          :selected="form.values.user_id"
          @select="
            (selected: number) => form.setFieldValue('user_id', selected)
          "
        />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
