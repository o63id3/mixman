<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'

import PaymentForm from './Partials/PaymentForm.vue'

import { useSubmit } from '@/Composables/submit'

import { Payment, User } from '@/types'
import { AlertCircle } from 'lucide-vue-next'

const props = defineProps<{
  payment: Payment
  users: Array<User>
  can: {
    delete: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    user_id: z.number({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().nullable().optional(),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    user_id: props.payment.user.id,
    amount: props.payment.amount,
    notes: props.payment.notes,
  },
})

const { submit, loading } = useSubmit(
  route('payments.update', props.payment.id),
  {
    method: 'patch',
    onSuccess: () => toast({ title: 'تم تعديل الدفعة المالية' }),
    onError: (errors) => setErrors(errors),
  },
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية رقم {{ payment.id }}
      </h2>
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
      @submit="onSubmit"
      :loading="loading"
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

      <PaymentForm
        :users="users"
        :selected="values.user_id"
        @select="(selected: number) => setFieldValue('user_id', selected)"
      />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
