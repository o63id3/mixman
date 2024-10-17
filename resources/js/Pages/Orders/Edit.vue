<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { formSchema } from './definitions'
import OrderForm from './Partials/OrderForm.vue'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import { DeleteLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { AlertCircle } from 'lucide-vue-next'

import CardsSection from './Partials/CardsSection.vue'
import FilesSection from './Partials/FilesSection.vue'

import { Card, Order, User } from '@/types'

const props = defineProps<{
  order: Order
  users: Array<User>
  cards: Array<Card>
  can: {
    delete: boolean
    cards: {
      create: boolean
    }
    files: {
      create: boolean
    }
  }
}>()

const initialValues = {
  user_id: props.order.user.id,
  status: props.order.status,
  notes: props.order.notes,
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
    label: `${props.order.id}#`,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
    </template>

    <Alert
      v-if="order.can.update && order.status !== 'معلق'"
      class="rounded-none sm:rounded-xl"
      variant="destructive"
    >
      <AlertCircle class="h-4 w-4" />
      <AlertTitle>انتباه!</AlertTitle>
      <AlertDescription>
        يمكنك تعديل أو حذف هذا الطلب حتى الساعة
        <span class="font-bold">11:59</span>!
      </AlertDescription>
    </Alert>

    <UpdateFormLayout
      class="mt-4"
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('orders.update', order.id)"
    >
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('orders.destroy', order.id)"
          @success="toast({ title: 'تم حذف الطلب' })"
        >
          حذف
        </DeleteLink>
      </template>

      <template #default="{ form }">
        <OrderForm
          :users="users"
          :selected="form.values.user_id"
          @select="
            (selected: number) => form.setFieldValue('user_id', selected)
          "
        />
      </template>
    </UpdateFormLayout>

    <CardsSection
      class="mt-4"
      :items="order.cards"
      :order="order"
      :cards="cards"
      :can-add-item="can.cards.create"
    />

    <FilesSection v-if="can.files.create" class="mt-4" :order="order" />
  </AuthenticatedLayout>
</template>
