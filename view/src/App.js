import React, { useContext, useEffect } from 'react';
import { Form, Container, Header, Col, Row, HeaderList, FeedbackList, Progress, Concialia } from './components';
import Store, { Context, functionsToDispatch } from './store';
import { api } from './lib/api'
import { someDate } from './lib/helpers'
const { set_header_enel } = functionsToDispatch;
function App() {

  const [{ isLoaded, xlsx, header, enel_header, finds, notFinds }, setContext] = useContext(Context)
  useEffect(
    function () {
      if (enel_header.length === 0) {
        api({ url: 'http://localhost:8080/fields', method: 'GET' }, r => {
          setContext(set_header_enel(r))
        })
      }
      console.log({ finds, notFinds, header, enel_header })

    }//npm install chart.js --save
    , [isLoaded, xlsx, enel_header, enel_header.length, finds, notFinds, header])
  return (
    <>
      <Header />
      <Container>
        <Row >
          <Col classes="my-2 col-12 col-lg-8 mx-lg-auto">
            <h2 className="h2 text-black-50"> Taixa de confiabilidade do documento</h2>
            <Progress />
            <HeaderList />
            <FeedbackList />
            <Form />
          </Col>

        </Row>
        <Row>
          <Col>
            {
              isLoaded && <Concialia
                labels_enel={enel_header}
                labels_xlsx={header}
                xlsx={xlsx}
              />
            }
          </Col>

        </Row>

      </Container>
    </>
  );
};
export default function () {
  return (
    <Store>
      <App />
    </Store>
  )
}
/*
<div class="container mt-5">
		<div class="row">

			<div class="col-sm-12 col-md-3 col-lg-4 mx-auto my-2 p-0"></div>*/