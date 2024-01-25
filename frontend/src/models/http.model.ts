export type ApiResponse = {
  response: {
    data: {
      data: { [key: string]: string[] };
    };
  };
  message: string;
};
