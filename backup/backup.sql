--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

-- Started on 2026-06-06 19:54:26

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 231 (class 1255 OID 16698)
-- Name: delete_catalogue(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.delete_catalogue(p_id integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- 1. On supprime d'abord les commandes liées (pour éviter l'erreur de clé étrangère)
    DELETE FROM commande_catalogue WHERE id_pc = p_id;
    
    -- 2. Ensuite, on supprime le PC du catalogue
    DELETE FROM pc_catalogue WHERE id_pc = p_id;
END;
$$;


ALTER FUNCTION public.delete_catalogue(p_id integer) OWNER TO postgres;

--
-- TOC entry 235 (class 1255 OID 16799)
-- Name: get_admin(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_admin(p_email text, p_password text) RETURNS TABLE(id_user integer, nom text, role character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN 
    RETURN QUERY SELECT 
         u.id_user, u.nom, u.role::varchar  
    FROM utilisateur u  
    WHERE u.email = p_email AND u.mot_de_passe = p_password AND u.role = 'admin'; 

    IF NOT FOUND THEN 
        RETURN QUERY SELECT -1, ''::text, 'none'::varchar; 
    END IF; 
END;  
$$;


ALTER FUNCTION public.get_admin(p_email text, p_password text) OWNER TO postgres;

--
-- TOC entry 229 (class 1255 OID 16696)
-- Name: insert_catalogue(character varying, text, character varying, character varying, character varying, character varying, character varying, numeric, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insert_catalogue(p_nom character varying, p_desc text, p_cpu character varying, p_mb character varying, p_gpu character varying, p_ram character varying, p_stock character varying, p_prix numeric, p_img character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO pc_catalogue (nom_modele, description, processeur, carte_mere, carte_graphique, memoire, stockage, prix, image_url)
    VALUES (p_nom, p_desc, p_cpu, p_mb, p_gpu, p_ram, p_stock, p_prix, p_img);
END;
$$;


ALTER FUNCTION public.insert_catalogue(p_nom character varying, p_desc text, p_cpu character varying, p_mb character varying, p_gpu character varying, p_ram character varying, p_stock character varying, p_prix numeric, p_img character varying) OWNER TO postgres;

--
-- TOC entry 233 (class 1255 OID 16734)
-- Name: insert_commande_catalogue(integer, integer, integer, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insert_commande_catalogue(p_user integer, p_pc integer, p_qty integer, p_prix numeric) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO commande_catalogue (id_user, id_pc, quantite, prix_total)
    VALUES (p_user, p_pc, p_qty, p_prix);
END;
$$;


ALTER FUNCTION public.insert_commande_catalogue(p_user integer, p_pc integer, p_qty integer, p_prix numeric) OWNER TO postgres;

--
-- TOC entry 232 (class 1255 OID 16715)
-- Name: insert_commande_custom(integer, text, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insert_commande_custom(p_user integer, p_desc text, p_prix numeric) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO commande_custom (id_user, description, prix_total) 
    VALUES (p_user, p_desc, p_prix);
END;
$$;


ALTER FUNCTION public.insert_commande_custom(p_user integer, p_desc text, p_prix numeric) OWNER TO postgres;

--
-- TOC entry 230 (class 1255 OID 16699)
-- Name: insert_user(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insert_user(p_nom character varying, p_email character varying, p_mdp character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO utilisateur (nom, email, mdp, role) VALUES (p_nom, p_email, p_mdp, 'client');
END;
$$;


ALTER FUNCTION public.insert_user(p_nom character varying, p_email character varying, p_mdp character varying) OWNER TO postgres;

--
-- TOC entry 234 (class 1255 OID 16697)
-- Name: update_catalogue(integer, character varying, text, character varying, character varying, character varying, character varying, character varying, numeric, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_catalogue(p_id integer, p_nom character varying, p_desc text, p_cpu character varying, p_mb character varying, p_gpu character varying, p_ram character varying, p_stock character varying, p_prix numeric, p_img character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE pc_catalogue 
    SET nom_modele = p_nom, 
        description = p_desc, 
        processeur = p_cpu, 
        carte_mere = p_mb, 
        carte_graphique = p_gpu, 
        memoire = p_ram, 
        stockage = p_stock, 
        prix = p_prix, 
        image_url = p_img
    WHERE id_pc = p_id;
END;
$$;


ALTER FUNCTION public.update_catalogue(p_id integer, p_nom character varying, p_desc text, p_cpu character varying, p_mb character varying, p_gpu character varying, p_ram character varying, p_stock character varying, p_prix numeric, p_img character varying) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 16634)
-- Name: categorie; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorie (
    id_cat integer NOT NULL,
    nom_cat character varying(100) NOT NULL
);


ALTER TABLE public.categorie OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16633)
-- Name: categorie_id_cat_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorie_id_cat_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorie_id_cat_seq OWNER TO postgres;

--
-- TOC entry 4968 (class 0 OID 0)
-- Dependencies: 217
-- Name: categorie_id_cat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorie_id_cat_seq OWNED BY public.categorie.id_cat;


--
-- TOC entry 228 (class 1259 OID 16717)
-- Name: commande_catalogue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commande_catalogue (
    id_commande integer NOT NULL,
    id_user integer NOT NULL,
    id_pc integer NOT NULL,
    quantite integer NOT NULL,
    prix_total numeric(10,2) NOT NULL,
    date_commande timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.commande_catalogue OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16716)
-- Name: commande_catalogue_id_commande_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.commande_catalogue_id_commande_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.commande_catalogue_id_commande_seq OWNER TO postgres;

--
-- TOC entry 4971 (class 0 OID 0)
-- Dependencies: 227
-- Name: commande_catalogue_id_commande_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.commande_catalogue_id_commande_seq OWNED BY public.commande_catalogue.id_commande;


--
-- TOC entry 226 (class 1259 OID 16701)
-- Name: commande_custom; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commande_custom (
    id_commande integer NOT NULL,
    id_user integer NOT NULL,
    description text NOT NULL,
    prix_total numeric(10,2) NOT NULL,
    date_commande timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.commande_custom OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16700)
-- Name: commande_custom_id_commande_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.commande_custom_id_commande_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.commande_custom_id_commande_seq OWNER TO postgres;

--
-- TOC entry 4974 (class 0 OID 0)
-- Dependencies: 225
-- Name: commande_custom_id_commande_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.commande_custom_id_commande_seq OWNED BY public.commande_custom.id_commande;


--
-- TOC entry 220 (class 1259 OID 16643)
-- Name: composant; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.composant (
    id_comp integer NOT NULL,
    nom character varying(200) NOT NULL,
    marque character varying(100),
    prix numeric(10,2) NOT NULL,
    stock integer DEFAULT 10,
    id_cat integer NOT NULL
);


ALTER TABLE public.composant OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16642)
-- Name: composant_id_comp_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.composant_id_comp_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.composant_id_comp_seq OWNER TO postgres;

--
-- TOC entry 4977 (class 0 OID 0)
-- Dependencies: 219
-- Name: composant_id_comp_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.composant_id_comp_seq OWNED BY public.composant.id_comp;


--
-- TOC entry 222 (class 1259 OID 16658)
-- Name: pc_catalogue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pc_catalogue (
    id_pc integer NOT NULL,
    nom_modele character varying(150) NOT NULL,
    description text,
    processeur character varying(150),
    carte_mere character varying(150),
    carte_graphique character varying(150),
    memoire character varying(150),
    stockage character varying(150),
    prix numeric(10,2) NOT NULL,
    image_url character varying(255) DEFAULT 'default_pc.png'::character varying
);


ALTER TABLE public.pc_catalogue OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16657)
-- Name: pc_catalogue_id_pc_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pc_catalogue_id_pc_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pc_catalogue_id_pc_seq OWNER TO postgres;

--
-- TOC entry 4980 (class 0 OID 0)
-- Dependencies: 221
-- Name: pc_catalogue_id_pc_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pc_catalogue_id_pc_seq OWNED BY public.pc_catalogue.id_pc;


--
-- TOC entry 224 (class 1259 OID 16685)
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utilisateur (
    id_user integer NOT NULL,
    nom character varying(100) NOT NULL,
    email character varying(255) NOT NULL,
    mdp character varying(255) NOT NULL,
    role character varying(20) DEFAULT 'client'::character varying
);


ALTER TABLE public.utilisateur OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16684)
-- Name: utilisateur_id_user_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utilisateur_id_user_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utilisateur_id_user_seq OWNER TO postgres;

--
-- TOC entry 4983 (class 0 OID 0)
-- Dependencies: 223
-- Name: utilisateur_id_user_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utilisateur_id_user_seq OWNED BY public.utilisateur.id_user;


--
-- TOC entry 4774 (class 2604 OID 16637)
-- Name: categorie id_cat; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorie ALTER COLUMN id_cat SET DEFAULT nextval('public.categorie_id_cat_seq'::regclass);


--
-- TOC entry 4783 (class 2604 OID 16720)
-- Name: commande_catalogue id_commande; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_catalogue ALTER COLUMN id_commande SET DEFAULT nextval('public.commande_catalogue_id_commande_seq'::regclass);


--
-- TOC entry 4781 (class 2604 OID 16704)
-- Name: commande_custom id_commande; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_custom ALTER COLUMN id_commande SET DEFAULT nextval('public.commande_custom_id_commande_seq'::regclass);


--
-- TOC entry 4775 (class 2604 OID 16646)
-- Name: composant id_comp; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.composant ALTER COLUMN id_comp SET DEFAULT nextval('public.composant_id_comp_seq'::regclass);


--
-- TOC entry 4777 (class 2604 OID 16661)
-- Name: pc_catalogue id_pc; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pc_catalogue ALTER COLUMN id_pc SET DEFAULT nextval('public.pc_catalogue_id_pc_seq'::regclass);


--
-- TOC entry 4779 (class 2604 OID 16688)
-- Name: utilisateur id_user; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur ALTER COLUMN id_user SET DEFAULT nextval('public.utilisateur_id_user_seq'::regclass);


--
-- TOC entry 4951 (class 0 OID 16634)
-- Dependencies: 218
-- Data for Name: categorie; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorie (id_cat, nom_cat) FROM stdin;
1	Processeur (CPU)
2	Carte Graphique (GPU)
3	Mémoire (RAM)
4	Carte Mère
5	Stockage (SSD)
6	Refroidissement
7	Boîtier
8	Alimentation
\.


--
-- TOC entry 4961 (class 0 OID 16717)
-- Dependencies: 228
-- Data for Name: commande_catalogue; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.commande_catalogue (id_commande, id_user, id_pc, quantite, prix_total, date_commande) FROM stdin;
1	3	1	1	560.00	2026-05-14 20:20:52.498194
2	3	2	1	899.00	2026-05-14 21:39:53.148122
3	3	2	1	899.00	2026-05-14 21:42:22.871263
5	4	2	1	899.00	2026-05-15 00:07:56.932177
6	3	2	1	899.00	2026-05-15 00:24:28.187905
7	2	1	1	560.00	2026-05-18 23:22:04.875065
9	3	5	1	2850.00	2026-06-02 14:23:03.205717
10	2	2	1	899.00	2026-06-02 15:20:18.933607
11	2	1	1	560.00	2026-06-02 16:46:01.169316
12	2	2	1	899.00	2026-06-02 16:46:33.404032
13	3	1	1	560.00	2026-06-06 15:48:32.294644
14	2	1	1	560.00	2026-06-06 15:52:26.514877
\.


--
-- TOC entry 4959 (class 0 OID 16701)
-- Dependencies: 226
-- Data for Name: commande_custom; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.commande_custom (id_commande, id_user, description, prix_total, date_commande) FROM stdin;
1	3	Processeur : Non spécifié\nCarte Mère : Non spécifié\nCarte Graphique : Non spécifié\nRAM : Non spécifié\nStockage : Non spécifié	0.00	2026-05-14 20:09:59.909079
2	3	Processeur : Non spécifié\nCarte Mère : Non spécifié\nCarte Graphique : Non spécifié\nRAM : Non spécifié\nStockage : Non spécifié	0.00	2026-05-14 20:09:59.918422
3	3	Processeur : Non spécifié\nCarte Mère : Non spécifié\nCarte Graphique : Non spécifié\nRAM : Non spécifié\nStockage : Non spécifié	0.00	2026-05-14 20:09:59.918817
4	3	Processeur : Non spécifié\nCarte Mère : Non spécifié\nCarte Graphique : Non spécifié\nRAM : Non spécifié\nStockage : Non spécifié	0.00	2026-05-14 20:09:59.919151
5	3	Processeur : AMD Ryzen 7 7800X3D\nCarte Mère : 0\nCarte Graphique : NVIDIA GeForce RTX 4070 SUPER 12Go\nRAM : 0\nStockage : 0	0.00	2026-05-14 20:09:59.91946
6	3	Processeur : AMD Ryzen 7 7800X3D\nCarte Mère : MSI MAG B650 TOMAHAWK WIFI\nCarte Graphique : AMD Radeon RX 7800 XT 16Go\nRAM : 32 Go (2x16) DDR4 3600MHz Kingston Fury\nStockage : SSD NVMe 1 To Samsung 980 Pro	1510.90	2026-05-14 20:09:59.919827
7	3	Processeur : Non spécifié\nCarte Mère : Non spécifié\nCarte Graphique : Non spécifié\nRAM : Non spécifié\nStockage : Non spécifié	0.00	2026-05-14 20:09:59.920142
8	3	Processeur : AMD Ryzen 7 7800X3D\nCarte Mère : Non spécifié\nCarte Graphique : Non spécifié\nRAM : Non spécifié\nStockage : Non spécifié\nCase : Non spécifié	399.00	2026-05-14 20:10:52.194332
9	4	Quantité : 1\nProcesseur : AMD Ryzen 7 7800X3D\nCarte Mère : Non spécifié\nCarte Graphique : NVIDIA GeForce RTX 4070 SUPER 12Go\nRAM : Non spécifié\nStockage : Non spécifié\nCase : Non spécifié	1058.00	2026-05-14 20:35:37.266973
10	3	Quantité : 1\nProcesseur : AMD Ryzen 7 7800X3D\nCarte Mère : MSI PRO H610M-G\nCarte Graphique : NVIDIA GeForce RTX 4070 SUPER 12Go\nRAM : 32 Go (2x16Go) DDR5 6000MHz CL30\nStockage : SSD NVMe 1 To Samsung 980 Pro\nBoîtier : Zalman T7 (Noir)	1445.89	2026-05-14 20:43:06.798168
11	3	Quantité : 1\nProcesseur : AMD Ryzen 7 7800X3D\nCarte Mère : ASUS ROG STRIX B760-F\nCarte Graphique : NVIDIA RTX 3060 12Go\nRAM : 32 Go (2x16Go) DDR5 6000MHz CL30\nStockage : SSD NVMe 1 To Crucial P3 Plus (Gen4)\nBoîtier : Lian Li Lancool 216	1266.99	2026-05-18 23:24:12.657557
12	2	Quantité : 1\nProcesseur : Intel Core i5-13600K\nCarte Mère : Gigabyte B550 GAMING X V2\nCarte Graphique : NVIDIA GeForce RTX 4060 8Go\nRAM : 16 Go (2x8) DDR4 3200MHz Corsair Vengeance\nStockage : SSD NVMe 500 Go Kingston\nBoîtier : Zalman T7 (Noir)	897.49	2026-06-02 16:46:16.833811
13	3	Quantité : 1\nProcesseur : AMD Ryzen 5 7600X\nCarte Mère : Gigabyte B550 GAMING X V2\nCarte Graphique : NVIDIA GeForce RTX 4070 SUPER 12Go\nRAM : 64 Go (2x32Go) DDR5 6000MHz\nStockage : SSD NVMe 1 To Samsung 980 Pro\nBoîtier : Corsair 4000D Airflow	1491.49	2026-06-06 15:43:33.704404
\.


--
-- TOC entry 4953 (class 0 OID 16643)
-- Dependencies: 220
-- Data for Name: composant; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.composant (id_comp, nom, marque, prix, stock, id_cat) FROM stdin;
1	AMD Ryzen 5 7600X	AMD	249.99	15	1
2	Intel Core i5-13600K	Intel	319.50	10	1
3	AMD Ryzen 7 7800X3D	AMD	399.00	5	1
4	NVIDIA GeForce RTX 4060 8Go	MSI	329.99	20	2
5	NVIDIA GeForce RTX 4070 SUPER 12Go	Asus	659.00	8	2
6	AMD Radeon RX 7800 XT 16Go	Sapphire	549.90	12	2
7	16 Go (2x8Go) DDR5 5200MHz	Corsair	79.99	30	3
8	32 Go (2x16Go) DDR5 6000MHz CL30	G.Skill	139.99	25	3
9	64 Go (2x32Go) DDR5 6000MHz	Corsair	269.50	10	3
10	MSI PRO H610M-G	\N	89.90	10	4
11	ASUS ROG STRIX B760-F	\N	239.00	10	4
12	SSD NVMe 500 Go Kingston	\N	45.00	10	5
13	SSD NVMe 1 To Samsung 980 Pro	\N	109.00	10	5
14	Ventirad Be Quiet! Pure Rock 2	\N	39.90	10	6
15	Watercooling MSI Mag Coreliquid 240R	\N	115.00	10	6
16	Zalman T7 (Noir)	\N	49.00	10	7
17	Corsair 4000D Airflow	\N	95.00	10	7
18	MSI MAG A650BN 650W	\N	59.00	10	8
19	Corsair RM850x 850W Gold	\N	145.00	10	8
20	Intel Core i5-12400F	\N	149.90	10	1
21	Intel Core i5-13600K	\N	329.90	10	1
22	Intel Core i7-14700K	\N	449.00	10	1
23	Intel Core i9-14900K	\N	629.00	10	1
24	AMD Ryzen 5 5600X	\N	159.00	10	1
25	AMD Ryzen 7 7800X3D	\N	399.90	10	1
26	AMD Ryzen 9 7950X	\N	599.00	10	1
27	NVIDIA RTX 3060 12Go	\N	299.00	10	2
28	NVIDIA RTX 4060 Ti 8Go	\N	419.00	10	2
29	NVIDIA RTX 4070 SUPER 12Go	\N	659.00	10	2
30	NVIDIA RTX 4080 SUPER 16Go	\N	1099.00	10	2
31	NVIDIA RTX 4090 24Go	\N	1899.00	10	2
32	AMD Radeon RX 7600 8Go	\N	289.00	10	2
33	AMD Radeon RX 7800 XT 16Go	\N	549.00	10	2
34	AMD Radeon RX 7900 XTX 24Go	\N	999.00	10	2
35	16 Go (2x8) DDR4 3200MHz Corsair Vengeance	\N	45.00	10	3
36	32 Go (2x16) DDR4 3600MHz Kingston Fury	\N	85.00	10	3
37	16 Go (2x8) DDR5 5200MHz Crucial	\N	65.00	10	3
38	32 Go (2x16) DDR5 6000MHz G.Skill Trident Z5	\N	129.00	10	3
39	64 Go (2x32) DDR5 6400MHz Corsair Dominator	\N	249.00	10	3
40	Gigabyte B550 GAMING X V2	\N	109.00	10	4
41	MSI MAG B650 TOMAHAWK WIFI	\N	219.00	10	4
42	ASUS TUF GAMING Z790-PLUS WIFI	\N	279.00	10	4
43	Gigabyte X670E AORUS MASTER	\N	499.00	10	4
44	SSD NVMe 1 To Crucial P3 Plus (Gen4)	\N	75.00	10	5
45	SSD NVMe 2 To Samsung 990 Pro	\N	189.00	10	5
46	SSD NVMe 4 To WD Black SN850X	\N	349.00	10	5
47	Disque Dur HDD 2 To Seagate Barracuda	\N	55.00	10	5
48	Ventirad Thermalright Peerless Assassin 120	\N	45.00	10	6
49	Ventirad Noctua NH-D15	\N	109.00	10	6
50	Watercooling Corsair iCUE H100i (240mm)	\N	149.00	10	6
51	Watercooling NZXT Kraken Elite 360 (360mm)	\N	289.00	10	6
52	NZXT H5 Flow (Noir)	\N	95.00	10	7
53	Lian Li Lancool 216	\N	115.00	10	7
54	Fractal Design North (Bois/Mesh)	\N	149.00	10	7
55	Lian Li O11 Dynamic EVO	\N	189.00	10	7
56	Seasonic Focus GX 750W Gold	\N	109.00	10	8
57	Corsair RM850e 850W Gold	\N	129.00	10	8
58	Be Quiet! Straight Power 12 1000W Platinum	\N	219.00	10	8
59	ASUS ROG Thor 1200W Platinum II	\N	349.00	10	8
\.


--
-- TOC entry 4955 (class 0 OID 16658)
-- Dependencies: 222
-- Data for Name: pc_catalogue; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pc_catalogue (id_pc, nom_modele, description, processeur, carte_mere, carte_graphique, memoire, stockage, prix, image_url) FROM stdin;
2	Pulse	Le Pulse est conçu pour les joueurs exigeants en 1080p Ultra. Sa RTX 4060 8GB permet de profiter du Ray Tracing et du DLSS 3, couplée au Ryzen 5 7600 pour une puissance de calcul moderne en DDR5. Équipé de 16GB de RAM haute performance, c’est le compagnon idéal pour un setup gaming réactif, évolutif et paré pour les futurs hits.	Intel Core i5-13600K	ASUS ROG STRIX B760-F	NVIDIA GeForce RTX 4070 SUPER 12Go	32 Go (2x16Go) DDR5 6000MHz CL30	SSD NVMe 500 Go Kingston	899.00	pulse.png
3	Horizon	L’Horizon offre une expérience haut de gamme en 1440p. La RTX 4070 12GB garantit un haut niveau de détails, tandis que l’Intel Core i5-13600K excelle en jeu comme en applicatif lourd. Avec 32GB de DDR5 et un stockage Gen4 ultra-rapide, ce PC est une station polyvalente parfaite pour jouer, streamer et créer sans aucune latence.	Intel Core i5-12400F	Gigabyte B550 GAMING X V2	AMD Radeon RX 7800 XT 16Go	32 Go (2x16Go) DDR5 6000MHz CL30	SSD NVMe 1 To Samsung 980 Pro	1450.00	horizon.png
4	Apex	Le PC Apex mise sur la puissance brute de l’architecture AMD. La Radeon RX 7900 XT avec ses 20GB de VRAM domine le jeu en 2K et 4K, soutenue par le légendaire Ryzen 7 7800X3D, le meilleur processeur gaming au monde. Un système taillé pour la compétition, offrant des fréquences d’images extrêmes et une fluidité absolue dans tous vos titres favoris.	AMD Ryzen 7 7800X3D	MSI MAG B650 TOMAHAWK WIFI	AMD Radeon RX 7800 XT 16Go	32 Go (2x16) DDR4 3600MHz Kingston Fury	SSD NVMe 4 To WD Black SN850X	1990.00	apex.png
1	Oxygen	Le PC Oxygen propose une configuration équilibrée et accessible, idéale pour débuter le gaming en Full HD. La GTX 1650 4GB assure une fluidité constante sur les titres Esport, tandis que l’Intel Core i3-13100F offre une réactivité exemplaire pour le quotidien. Avec 8GB de DDR4 et un SSD NVMe, profitez d’un système rapide et efficace pour vos premières sessions de jeu.	Intel Core i5-13600K	MSI PRO H610M-G	NVIDIA GeForce RTX 4070 SUPER 12Go	16 Go (2x8Go) DDR5 5200MHz	SSD NVMe 500 Go Kingston	560.00	oxygen.png
5	Eon	Eon est une machine de guerre destinée aux créateurs et aux joueurs 4K. La RTX 4080 Super 16GB assure des performances graphiques de pointe, tandis que l’i7-14700K gère le multitâche intensif avec aisance. Ses 64GB de RAM DDR5 permettent de travailler sur des projets vidéo lourds tout en garantissant une expérience gaming fluide et immersive au quotidien.	Intel Core i7-14700K	ASUS ROG STRIX B760-F	NVIDIA RTX 3060 12Go	16 Go (2x8) DDR4 3200MHz Corsair Vengeance	SSD NVMe 1 To Samsung 980 Pro	2850.00	eon.png
6	Nova	Le Nova incarne le summum de la technologie actuelle. Équipé de la RTX 4090 24GB et du Ryzen 9 7950X3D, il repousse les limites du possible en 4K Ultra et en rendu 3D. Avec un SSD NVMe Gen5 révolutionnaire et 64GB de mémoire ultra-rapide, c’est le choix ultime pour ceux qui ne tolèrent aucun compromis. Un PC d’exception, rapide, puissant et futuriste.	AMD Ryzen 9 7950X	Gigabyte X670E AORUS MASTER	AMD Radeon RX 7900 XTX 24Go	64 Go (2x32) DDR5 6400MHz Corsair Dominator	Disque Dur HDD 2 To Seagate Barracuda	4290.00	nova.png
\.


--
-- TOC entry 4957 (class 0 OID 16685)
-- Dependencies: 224
-- Data for Name: utilisateur; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utilisateur (id_user, nom, email, mdp, role) FROM stdin;
2	user1	user1@gmail.com	$2y$10$Vds6ebwEGHJlfBSXVGQJv.z1MlZLyIUVpgJh/FamYMJ.IRonGWhja	client
3	admin	admin@exemple.com	$2y$10$OlHd/5BaqriQNJI3uNH1lurP4UfM9G5SjJgCN2udDAZIs4ktV8g72	admin
4	user2	user2@gmail.com	$2y$10$u1BdI4zY0wx7fVvk6bwO2O5FEukZGURVj0qzMh2UTvkdqyAwiVP1a	client
\.


--
-- TOC entry 4985 (class 0 OID 0)
-- Dependencies: 217
-- Name: categorie_id_cat_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorie_id_cat_seq', 1, false);


--
-- TOC entry 4986 (class 0 OID 0)
-- Dependencies: 227
-- Name: commande_catalogue_id_commande_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.commande_catalogue_id_commande_seq', 14, true);


--
-- TOC entry 4987 (class 0 OID 0)
-- Dependencies: 225
-- Name: commande_custom_id_commande_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.commande_custom_id_commande_seq', 13, true);


--
-- TOC entry 4988 (class 0 OID 0)
-- Dependencies: 219
-- Name: composant_id_comp_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.composant_id_comp_seq', 59, true);


--
-- TOC entry 4989 (class 0 OID 0)
-- Dependencies: 221
-- Name: pc_catalogue_id_pc_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pc_catalogue_id_pc_seq', 10, true);


--
-- TOC entry 4990 (class 0 OID 0)
-- Dependencies: 223
-- Name: utilisateur_id_user_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utilisateur_id_user_seq', 4, true);


--
-- TOC entry 4786 (class 2606 OID 16641)
-- Name: categorie categorie_nom_cat_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_nom_cat_key UNIQUE (nom_cat);


--
-- TOC entry 4788 (class 2606 OID 16639)
-- Name: categorie categorie_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_pkey PRIMARY KEY (id_cat);


--
-- TOC entry 4800 (class 2606 OID 16723)
-- Name: commande_catalogue commande_catalogue_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_catalogue
    ADD CONSTRAINT commande_catalogue_pkey PRIMARY KEY (id_commande);


--
-- TOC entry 4798 (class 2606 OID 16709)
-- Name: commande_custom commande_custom_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_custom
    ADD CONSTRAINT commande_custom_pkey PRIMARY KEY (id_commande);


--
-- TOC entry 4790 (class 2606 OID 16649)
-- Name: composant composant_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.composant
    ADD CONSTRAINT composant_pkey PRIMARY KEY (id_comp);


--
-- TOC entry 4792 (class 2606 OID 16666)
-- Name: pc_catalogue pc_catalogue_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pc_catalogue
    ADD CONSTRAINT pc_catalogue_pkey PRIMARY KEY (id_pc);


--
-- TOC entry 4794 (class 2606 OID 16695)
-- Name: utilisateur utilisateur_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_email_key UNIQUE (email);


--
-- TOC entry 4796 (class 2606 OID 16693)
-- Name: utilisateur utilisateur_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_pkey PRIMARY KEY (id_user);


--
-- TOC entry 4803 (class 2606 OID 16729)
-- Name: commande_catalogue commande_catalogue_id_pc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_catalogue
    ADD CONSTRAINT commande_catalogue_id_pc_fkey FOREIGN KEY (id_pc) REFERENCES public.pc_catalogue(id_pc);


--
-- TOC entry 4804 (class 2606 OID 16724)
-- Name: commande_catalogue commande_catalogue_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_catalogue
    ADD CONSTRAINT commande_catalogue_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.utilisateur(id_user);


--
-- TOC entry 4802 (class 2606 OID 16710)
-- Name: commande_custom commande_custom_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_custom
    ADD CONSTRAINT commande_custom_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.utilisateur(id_user);


--
-- TOC entry 4801 (class 2606 OID 16650)
-- Name: composant fk_comp_cat; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.composant
    ADD CONSTRAINT fk_comp_cat FOREIGN KEY (id_cat) REFERENCES public.categorie(id_cat);


--
-- TOC entry 4967 (class 0 OID 0)
-- Dependencies: 218
-- Name: TABLE categorie; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.categorie TO anonyme;


--
-- TOC entry 4969 (class 0 OID 0)
-- Dependencies: 217
-- Name: SEQUENCE categorie_id_cat_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,USAGE ON SEQUENCE public.categorie_id_cat_seq TO anonyme;
GRANT ALL ON SEQUENCE public.categorie_id_cat_seq TO PUBLIC;


--
-- TOC entry 4970 (class 0 OID 0)
-- Dependencies: 228
-- Name: TABLE commande_catalogue; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.commande_catalogue TO PUBLIC;


--
-- TOC entry 4972 (class 0 OID 0)
-- Dependencies: 227
-- Name: SEQUENCE commande_catalogue_id_commande_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.commande_catalogue_id_commande_seq TO PUBLIC;


--
-- TOC entry 4973 (class 0 OID 0)
-- Dependencies: 226
-- Name: TABLE commande_custom; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.commande_custom TO PUBLIC;


--
-- TOC entry 4975 (class 0 OID 0)
-- Dependencies: 225
-- Name: SEQUENCE commande_custom_id_commande_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.commande_custom_id_commande_seq TO PUBLIC;


--
-- TOC entry 4976 (class 0 OID 0)
-- Dependencies: 220
-- Name: TABLE composant; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.composant TO anonyme;


--
-- TOC entry 4978 (class 0 OID 0)
-- Dependencies: 219
-- Name: SEQUENCE composant_id_comp_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,USAGE ON SEQUENCE public.composant_id_comp_seq TO anonyme;
GRANT ALL ON SEQUENCE public.composant_id_comp_seq TO PUBLIC;


--
-- TOC entry 4979 (class 0 OID 0)
-- Dependencies: 222
-- Name: TABLE pc_catalogue; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.pc_catalogue TO anonyme;
GRANT ALL ON TABLE public.pc_catalogue TO PUBLIC;


--
-- TOC entry 4981 (class 0 OID 0)
-- Dependencies: 221
-- Name: SEQUENCE pc_catalogue_id_pc_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.pc_catalogue_id_pc_seq TO PUBLIC;


--
-- TOC entry 4982 (class 0 OID 0)
-- Dependencies: 224
-- Name: TABLE utilisateur; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.utilisateur TO PUBLIC;


--
-- TOC entry 4984 (class 0 OID 0)
-- Dependencies: 223
-- Name: SEQUENCE utilisateur_id_user_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.utilisateur_id_user_seq TO PUBLIC;


-- Completed on 2026-06-06 19:54:26

--
-- PostgreSQL database dump complete
--

