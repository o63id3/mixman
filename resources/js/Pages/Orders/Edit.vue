<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import OrderForm from './Partials/OrderForm.vue'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { DeleteLink } from '@/Components/links'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

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
            <BreadcrumbPage>
              {{ order.id }}#
              <span class="text-xs font-normal tracking-wide">
                ({{ order.manager.name }})
              </span>
            </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
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
