import './App.css';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import Layout from './components/MainLayout/MainLayout';
import Home from './views/DepartmentList/DepartmentList';
import NotFound from './views/NotFound/NotFound';
import { Toaster } from 'react-hot-toast';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<Home />} />
          <Route path="*" element={<NotFound />} />
        </Route>
      </Routes>
      <Toaster position="top-center" />
    </BrowserRouter>
  );
}

export default App;
