import React, { useContext, useEffect } from "react";
import {
  Form,
  Container,
  Header,
  Col,
  Row,
  HeaderList,
  FeedbackList,
  Progress
} from "./components/";
import Store, { Context } from "./store";
function App() {
  const [{ isLoaded, toVerify ,...rest}] = useContext(Context);
  useEffect(() => {
    console.log({ isLoaded, toVerify, ...rest });
  }, [toVerify]);

  return (
    <>
      <Header />
      <Container>
        <Row>
          <Col classes="col-sm-12 col-md-3 col-lg-4 mx-auto my-2 p-0">
            <HeaderList />
            <FeedbackList />
          </Col>
          <Col classes="col-sm-12 col-md-6 col-lg-8 mx-auto my-2">
            <Form />
            <Col classes="col-8 mr-auto">
              <h2 className="h2  text-black-50">
                {" "}
                Taixa de confiabilidade do documento
              </h2>
              <Progress />
              <div>
                <h4>
                  Associe as colunas da plailha com as colunas na tabela
                </h4>

                <label htmlFor="" />
                <select name="">
                  <option />
                </select>
              </div>
            </Col>
          </Col>
        </Row>
      </Container>
    </>
  );
}
export default function() {
  return (
    <Store>
      <App />
    </Store>
  );
}
/*
 *useEffect(
 *        function () {
 *            if (toVerify.length > 1) {
 *                console.log(toVerify);
 *                const myAsyncIterable = {};
 *                myAsyncIterable[Symbol.asyncIterator] = async function* () {
 *                    let index = 1;
 *                    const veri = toVerify.slice(Number((42416 - 3)), toVerify.length);
 *                    for (const k of veri) {
 *                        console.log(updateProgress(index, veri.length), index, veri.length);
 *                        yield await (
 *                            (
 *                                await axios.post('http://localhost:8080/insert',
 *                                    {
 *                                        table: "enel_arrecadacao_tabela ",
 *                                        fields: " `numeroCliente`, `dvNumeroCliente`, `referencia`, `dataPagamento`, `codProduto`, `empresaParc`,`correlativoDocumento`, `tipoDocumento` ",
 *                                        values: [`${k[0]}`, `${k[1]}`, `${k[5]}`, `${k[6]}`, `${k[4]}`, `${k[9]}`, `${k[7]}`, `${k[8]}`]
 *                                    }
 *                                )
 *                            )
 *                        ).data;
 *                        index++;
 *                    }
 *                };
 *                (async () => {
 *                    for await (const x of myAsyncIterable) {
 *                        console.log(x);
 *                    }
 *                })();
 *
 *            }
 *        }
 *        , [isLoaded, toVerify]);
 */
