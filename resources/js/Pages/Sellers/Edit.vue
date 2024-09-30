<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Region, User } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import SellerForm from './Partials/SellerForm.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import SecondaryLink from '@/Components/links/SecondaryLink.vue'

const props = defineProps<{
  seller: User
  regions: Array<Region>
  can: {
    delete: boolean
    activate: boolean
    deactivate: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    region_id: z.string({ message: 'هذا الحقل مطلوب' }),
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    username: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'اسم المستخدم يجيب ان يكون حرفين على الاقل' }),
    password: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(4, { message: 'كلمة المرور يجب ان تكون 4 احرف على الاقل' })
      .optional(),
    seller_percentage: z
      .number({ message: 'هذا الحقل مطلوب' })
      .min(1, { message: 'نسبة الربح يجب أن تكون أكبر من 1' })
      .max(100, { message: 'نسبة الربح يجب أن تكون أقل من 100' }),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    region_id: String(props.seller.region?.id),
    name: props.seller.name,
    username: props.seller.username,
    seller_percentage: Math.round(props.seller.percentage * 100),
    contact_info: props.seller.contact_info ?? undefined,
    notes: props.seller.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('sellers.update', props.seller.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل نقطة البيع' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Sellers" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ seller.name }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" can-update :can-delete="can.delete">
      <template #buttons>
        <DeleteLink
          v-if="seller.active && can.deactivate"
          :href="route('users.deactivate', seller.id)"
          @success="toast({ title: 'تم تعطيل حساب البائع' })"
          method="POST"
        >
          تعطيل الحساب
        </DeleteLink>
        <SecondaryLink
          v-else-if="!seller.active && can.activate"
          method="DELETE"
          :href="route('users.activate', seller.id)"
          @success="toast({ title: 'تم تفعيل حساب البائع' })"
        >
          تفعيل الحساب
        </SecondaryLink>
      </template>

      <SellerForm :regions="regions" />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
