<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Payment, User } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import PaymentForm from './Partials/PaymentForm.vue'

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
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    user_id: props.payment.user.id,
    amount: props.payment.amount,
    notes: props.payment.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('payments.update', props.payment.id), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم تعديل الدفعة المالية' })
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية رقم {{ payment.id }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" can-update>
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
