export enum ModalTypesE {
  Create = 'create',
  Edit = 'edit'
}

type ModalT = ModalTypesE.Create | ModalTypesE.Edit | null

export interface ModalI {
  isModalVisible: boolean,
  type: ModalT,
  id: number | null,
  title: string,
  description: string
}
