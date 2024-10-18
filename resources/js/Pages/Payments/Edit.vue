<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UpdateFormLayout from '@/Layouts/UpdateFormLayout.vue'

import { formSchema } from './definitions'
import PaymentForm from './Partials/PaymentForm.vue'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import { DeleteLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

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

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الدفعات',
    route: route('payments.index'),
  },
  {
    label: `${props.payment.id}#`,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
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
