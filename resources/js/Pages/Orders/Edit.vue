<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { vAutoAnimate } from '@formkit/auto-animate/vue'

import { Button, buttonVariants } from '@/Components/ui/button'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { toast } from '@/Components/ui/toast'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/Components/ui/command'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { Order, User } from '@/types'
import { cn } from '@/lib/utils'
import { CheckIcon } from 'lucide-vue-next'
import { CaretSortIcon } from '@radix-icons/vue'

const statuses: Record<string, string> = {
  P: 'جاري',
  X: 'لاغي',
}

if (usePage().props.auth.user.admin) {
  statuses.C = 'مكتمل'
}

const props = defineProps<{
  order: Order
  sellers: Array<User>
}>()

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    seller_id: props.order.seller.id,
    status: props.order.status,
    notes: props.order.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('orders.update', props.order.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل الطلب' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلب رقم: {{ order.id }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField name="seller_id">
                  <FormItem class="flex flex-col gap-2">
                    <FormLabel>نقطة البيع</FormLabel>
                    <Popover>
                      <PopoverTrigger as-child>
                        <FormControl>
                          <Button
                            :disabled="!order.can.update"
                            variant="outline"
                            role="combobox"
                            :class="
                              cn(
                                'justify-between',
                                !values.seller_id && 'text-muted-foreground',
                              )
                            "
                          >
                            {{
                              values.seller_id
                                ? sellers.find(
                                    (seller) => seller.id === values.seller_id,
                                  )?.name
                                : 'اختر نقطة بيع...'
                            }}
                            <CaretSortIcon
                              class="ml-2 h-4 w-4 shrink-0 opacity-50"
                            />
                          </Button>
                        </FormControl>
                      </PopoverTrigger>
                      <PopoverContent class="w-full p-0">
                        <Command>
                          <CommandInput
                            class="text-right"
                            placeholder="ابحث عن نقطة بيع"
                          />
                          <CommandEmpty>لا يوجد تطابق!</CommandEmpty>
                          <CommandList>
                            <CommandGroup>
                              <CommandItem
                                v-for="seller in sellers"
                                :key="seller.id"
                                :value="seller.name"
                                @select="
                                  () => setFieldValue('seller_id', seller.id)
                                "
                              >
                                <div
                                  class="flex w-full items-center justify-between"
                                >
                                  <CheckIcon
                                    :class="
                                      cn(
                                        'ml-auto h-4 w-4',
                                        seller.id === values.seller_id
                                          ? 'opacity-100'
                                          : 'opacity-0',
                                      )
                                    "
                                  />
                                  <div class="flex-1 text-right">
                                    {{ seller.name }}
                                  </div>
                                </div>
                              </CommandItem>
                            </CommandGroup>
                          </CommandList>
                        </Command>
                      </PopoverContent>
                    </Popover>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="status">
                  <FormItem>
                    <FormLabel>الحالة</FormLabel>
                    <FormControl>
                      <Select v-bind="componentField">
                        <SelectTrigger>
                          <SelectValue placeholder="اختر حالة الطلب" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectGroup>
                            <SelectItem
                              v-for="(item, key) in statuses"
                              :key="key"
                              :value="key"
                              :disabled="!order.can.update"
                            >
                              {{ item }}
                            </SelectItem>
                          </SelectGroup>
                        </SelectContent>
                      </Select>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="notes">
                  <FormItem class="md:col-span-2" v-auto-animate>
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea
                        :disabled="!order.can.update"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <div class="flex gap-2">
                <Button v-if="order.can.update" type="submit"> تعديل </Button>
                <Link
                  v-if="order.can.delete"
                  :href="route('orders.destroy', order.id)"
                  method="delete"
                  as="button"
                  type="button"
                  :class="cn(buttonVariants({ variant: 'destructive' }))"
                  @success="toast({ title: 'تم حذف الطلب' })"
                >
                  حذف
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
