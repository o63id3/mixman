<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import PaymentForm from './Partials/PaymentForm.vue'

import { toast } from '@/Components/ui/toast'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import { DeleteLink } from '@/Components/links'

import { AlertCircle } from 'lucide-vue-next'

import { Payment, User } from '@/types'

const props = defineProps<{
  payment: Payment
  users: Array<User>
  can: {
    delete: boolean
  }
}>()

const initialValues = {
  user_id: props.payment.user.id,
  amount: props.payment.amount,
  notes: props.payment.notes,
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
            <BreadcrumbLink :href="route('payments.index')">
              الدفعات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>{{ payment.id }}#</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <Alert
      v-if="payment.can.update"
      class="rounded-none sm:rounded-xl"
      variant="destructive"
    >
      <AlertCircle class="h-4 w-4" />
      <AlertTitle>انتباه!</AlertTitle>
      <AlertDescription>
        يمكنك تعديل أو حذف هذه الدفعة حتى الساعة
        <span class="font-bold">11:59</span>!
      </AlertDescription>
    </Alert>

    <UpdateFormLayout
      class="mt-4"
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('payments.update', payment.id)"
      @success="toast({ title: 'تم تعديل الدفعة المالية' })"
      can-update
    >
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('payments.destroy', payment.id)"
          @success="toast({ title: 'تم حذف الدفعة' })"
        >
          حذف
        </DeleteLink>
      </template>

      <template #default="{ form }">
        <PaymentForm
          :users="users"
          :selected="form.values.user_id"
          @select="
            (selected: number) => form.setFieldValue('user_id', selected)
          "
        />
      </template>
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
