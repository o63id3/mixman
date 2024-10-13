<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import OrderForm from './Partials/OrderForm.vue'

import { Card, User } from '@/types'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { CardTitle } from '@/Components/ui/card'

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
    <template #secondaryHeader>
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('dashboard')">
              الرئيسة
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('orders.index')">
              الطلبات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>إنشاء</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('orders.store')"
      @success="toast({ title: 'تم إضافة الطلب' })"
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
