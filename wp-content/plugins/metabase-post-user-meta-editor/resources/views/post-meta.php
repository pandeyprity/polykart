<div id="metabase-app" x-data="metabase" x-init="init(<?php esc_attr_e(wp_json_encode($data)); ?>)" class="tw-px-4 sm:tw-px-6 lg:tw-px-8">
  <div class="tw-mt-8 tw-flex tw-flex-col">
    <div class="tw--my-2 tw--mx-4 tw-overflow-x-auto sm:tw--mx-6 lg:tw--mx-8">
      <div class="tw-inline-block tw-min-w-full tw-py-2 tw-align-middle md:tw-px-6 lg:tw-px-8">
        <div class="tw-overflow-hidden tw-shadow-none tw-ring-1 tw-ring-black tw-ring-opacity-5">

          <table class="tw-w-full tw-divide-y tw-divide-gray-300 tw-table-fixed">
            <thead style="background: #f0f0f1;">
              <tr class="">
                <th scope="col" class="tw-py-3.5 tw-pl-4 tw-w-4/12 tw-pr-4 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900 sm:tw-pl-6">Key</th>
                <th scope="col" class="tw-px-4 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900">Value</th>
                <th scope="col" class="tw-px-4 tw-w-1/12 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900"></th>
              </tr>
            </thead>
            <tbody class="tw-divide-y tw-divide-gray-200 tw-bg-white">
              <template x-for="(field, index) in fields" :key="index">
                <tr class="">
                  <td :class="{'tw-opacity-20' : deleted.includes(index)}" class="tw-whitespace-nowrap tw-py-4 tw-pl-4 tw-pr-4 tw-text-sm tw-font-medium tw-text-gray-700 sm:tw-pl-6 tw-overflow-auto tw-border-r tw-border-gray-200">
                    <pre x-text="field.key"></pre>
                  </td>
                  <td :class="{'tw-opacity-20' : deleted.includes(index)}" class="tw-whitespace-nowrap tw-overflow-auto tw-p-4 tw-text-sm tw-text-gray-700">
                    <pre x-text="dump(field.value)"></pre>
                  </td>
                  <td x-show="!deleted.includes(index)" class="tw-whitespace-nowrap tw-overflow-auto tw-p-4 tw-text-sm tw-text-gray-500">
                    <svg x-show="loading == index" class="tw-animate-spin tw--ml-1 tw-mr-3 tw-h-5 tw-w-5 tw-text-wp-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                      <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <a x-on:click.prevent="trash(field, index)" x-show="confirmDelete === index && loading != index" x-on:click.away="confirmDelete = false" href="#" class="tw-text-xs tw-text-red-500 hover:tw-text-red-600"><?php esc_html_e('Confirm', 'metabase'); ?></a>
                    <a x-on:click.prevent="confirmDelete = index" x-show="confirmDelete !== index" href="#" class="tw-text-red-500 hover:tw-text-red-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-4 tw-h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                      </svg>
                      <span class="tw-sr-only"><?php esc_html_e('Delete', 'metabase'); ?></span>
                    </a>
                  </td>
                </tr>

              </template>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>

</div>